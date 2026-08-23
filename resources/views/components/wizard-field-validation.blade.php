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
            var msg = wrap.querySelector(':scope > .field-format-err');
            if (msg) msg.remove();
        }
        input.removeAttribute('aria-invalid');
    }

    // ── Rule inference ──────────────────────────────────────────────────────
    var PHONE_RE = /^[6-9][0-9]{9}$/;
    var EMAIL_RE = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    var PIN_RE   = /^[1-9][0-9]{5}$/;

    function inferKind(input) {
        var explicit = input.dataset.validate;
        if (explicit) return explicit;

        var name = (input.getAttribute('name') || '').toLowerCase();
        var type = (input.getAttribute('type') || '').toLowerCase();

        if (type === 'email' || /email/.test(name)) return 'email';
        if (/(^|_)pin(_|$)|pin_code|postal_address_pin/.test(name)) return 'pincode';
        if (/phone|contact_number|mobile/.test(name)) return 'phone';
        if (/latitude/.test(name)) return 'latitude';
        if (/longitude/.test(name)) return 'longitude';
        if (type === 'number') return 'number';
        return 'text';
    }

    // Integers unless the markup says otherwise (step="0.01" / step="any" /
    // step="0.1"). Most of these fields — floors, bedrooms, bathrooms, dock
    // counts — are non-negative whole numbers.
    function allowsDecimal(input) {
        var step = (input.getAttribute('step') || '').toLowerCase();
        return step === 'any' || (step !== '' && parseFloat(step) % 1 !== 0);
    }

    function allowsNegative(input) {
        var min = input.getAttribute('min');
        return min !== null && parseFloat(min) < 0;
    }

    // ── Input restriction (fires while typing) ──────────────────────────────
    function restrict(input, kind) {
        var before = input.value;
        var v = before;

        if (kind === 'phone') {
            v = v.replace(/\D/g, '').slice(0, 10);
        } else if (kind === 'pincode') {
            v = v.replace(/\D/g, '').slice(0, 6);
        } else if (kind === 'number' || kind === 'latitude' || kind === 'longitude') {
            var dec = kind !== 'number' || allowsDecimal(input);
            var neg = kind !== 'number' || allowsNegative(input);
            // A type="number" control reports '' for text the browser can't
            // parse, so restriction here is mainly for type="text" numerics;
            // it is still applied uniformly so both behave the same.
            var pattern = dec ? /[^0-9.\-]/g : /[^0-9\-]/g;
            v = v.replace(pattern, '');
            if (!neg) v = v.replace(/-/g, '');
            else v = v.replace(/(?!^)-/g, '');
            if (dec) {
                var parts = v.split('.');
                if (parts.length > 2) v = parts.shift() + '.' + parts.join('');
            }
        }

        var maxlen = parseInt(input.getAttribute('maxlength'), 10);
        if (!isNaN(maxlen) && v.length > maxlen) v = v.slice(0, maxlen);

        if (v !== before) {
            var pos = input.selectionStart;
            input.value = v;
            // Keep the caret where the user was typing rather than jumping
            // to the end, which is what a naive reassignment would do.
            if (input.type !== 'number' && pos !== null) {
                try { input.setSelectionRange(pos - 1, pos - 1); } catch (e) {}
            }
            return true; // something was stripped
        }
        return false;
    }

    // ── Full-rule validation ────────────────────────────────────────────────
    // `enforceRequired` is false while the user is merely typing/blurring and
    // true when they try to advance a step or submit, so an untouched empty
    // field doesn't shout at them but also can't slip past "Save & Next".
    function validateField(input, enforceRequired) {
        if (input.disabled || input.type === 'hidden') return true;

        var kind = inferKind(input);
        var value = (input.value || '').trim();
        var isRequired = input.hasAttribute('required');

        if (!value) {
            if (enforceRequired && isRequired) {
                showFieldError(input, input.tagName === 'SELECT'
                    ? 'Please select an option.'
                    : 'This field is required.');
                return false;
            }
            clearFieldError(input); // empty + optional is fine
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

        if (kind === 'number' || kind === 'latitude' || kind === 'longitude') {
            if (isNaN(parseFloat(value)) || !/^-?\d*\.?\d+$/.test(value)) {
                showFieldError(input, 'Enter a valid number.');
                return false;
            }
            var num = parseFloat(value);

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
                    showFieldError(input, 'Enter a whole number.');
                    return false;
                }
                var min = input.getAttribute('min');
                var max = input.getAttribute('max');
                var hasMin = min !== null && min !== '';
                var hasMax = max !== null && max !== '';
                if (hasMin && hasMax && (num < parseFloat(min) || num > parseFloat(max))) {
                    showFieldError(input, 'Enter a value between ' + min + ' and ' + max + '.');
                    return false;
                }
                if (hasMin && !hasMax && num < parseFloat(min)) {
                    showFieldError(input, 'Value must be ' + min + ' or more.');
                    return false;
                }
                if (hasMax && !hasMin && num > parseFloat(max)) {
                    showFieldError(input, 'Value must be ' + max + ' or less.');
                    return false;
                }
            }
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
        return el && (el.tagName === 'INPUT' || el.tagName === 'SELECT' || el.tagName === 'TEXTAREA')
            && el.type !== 'hidden' && el.type !== 'file'
            && el.type !== 'checkbox' && el.type !== 'radio'
            && el.type !== 'submit' && el.type !== 'button';
    }

    document.addEventListener('input', function (e) {
        if (!isCandidate(e.target)) return;
        var kind = inferKind(e.target);
        var stripped = restrict(e.target, kind);

        if (stripped && (kind === 'phone' || kind === 'pincode')) {
            showFieldError(e.target, kind === 'phone'
                ? 'Phone number can only contain digits — other characters are ignored.'
                : 'PIN code can only contain digits — other characters are ignored.');
            return;
        }
        // Re-check live only once they've already left the field once, so a
        // corrected value clears its error immediately.
        if (touched.has(e.target)) validateField(e.target, false);
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

    window.ZendoFieldValidation = {
        validateField: validateField,
        validateContainer: validateContainer,
        showFieldError: showFieldError,
        clearFieldError: clearFieldError,
    };
})();
</script>
